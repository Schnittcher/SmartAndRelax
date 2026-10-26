<?php

declare(strict_types=1);

require_once __DIR__ . '/../libs/MQTTHelper.php';

class SmartAndRelax extends IPSModuleStrict
{
    use MQTTHelper;

    private const STATUS_TOPIC_MISSING = 201;

    // Werte der Variable HeatState
    private const HEAT_OFF = 0;
    private const HEAT_TARGET_REACHED = 1;
    private const HEAT_HEATING = 2;
    private const HEAT_WAITING_FOR_PUMP = 3;

    // Kommandos des Pools (Feld CMD im Topic <MQTTTopic>/command)
    private const COMMANDS = [
        'TargetTemperature' => 0,
        'Air'               => 2,
        'Heat'              => 3,
        'Pump'              => 4
    ];

    public function GetCompatibleParents(): string
    {
        return json_encode([
            'type'      => 'connect',
            'moduleIDs' => ['{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}']
        ]);
    }

    public function Create(): void
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyString('MQTTTopic', 'layzspa');

        $this->RegisterVariableBoolean('Lock', $this->Translate('Lock'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'OPTIONS'      => json_encode([
                [
                    'Value'       => true,
                    'Caption'     => $this->Translate('Locked'),
                    'IconActive'  => false,
                    'IconValue'   => 'Information',
                    'ColorActive' => true,
                    'ColorValue'  => 65280
                ],
                [
                    'Value'       => false,
                    'Caption'     => $this->Translate('Unlocked'),
                    'IconActive'  => false,
                    'IconValue'   => 'Information',
                    'ColorActive' => true,
                    'ColorValue'  => 16711680
                ]
            ])
        ], 0);
        $this->EnableAction('Lock');

        $this->RegisterVariableBoolean('Power', $this->Translate('Power'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_SWITCH
        ], 1);
        $this->EnableAction('Power');

        $this->RegisterVariableBoolean('Air', $this->Translate('Air'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_SWITCH
        ], 3);
        $this->EnableAction('Air');

        $this->RegisterVariableBoolean('Heat', $this->Translate('Heat'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_SWITCH
        ], 4);
        $this->EnableAction('Heat');

        $this->RegisterVariableInteger('HeatState', $this->Translate('Heating State'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_ENUMERATION,
            'OPTIONS'      => json_encode([
                [
                    'Value'       => self::HEAT_OFF,
                    'Caption'     => $this->Translate('Off'),
                    'IconActive'  => false,
                    'IconValue'   => '',
                    'Color'       => -1
                ],
                [
                    'Value'       => self::HEAT_TARGET_REACHED,
                    'Caption'     => $this->Translate('Target temperature reached'),
                    'IconActive'  => false,
                    'IconValue'   => '',
                    'Color'       => 65280
                ],
                [
                    'Value'       => self::HEAT_HEATING,
                    'Caption'     => $this->Translate('Heating'),
                    'IconActive'  => false,
                    'IconValue'   => '',
                    'Color'       => 16711680
                ],
                [
                    'Value'       => self::HEAT_WAITING_FOR_PUMP,
                    'Caption'     => $this->Translate('Waiting for pump'),
                    'IconActive'  => false,
                    'IconValue'   => '',
                    'Color'       => 16753920
                ]
            ])
        ], 5);

        $this->RegisterVariableBoolean('Pump', $this->Translate('Pump'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_SWITCH
        ], 7);
        $this->EnableAction('Pump');

        $this->RegisterVariableInteger('TargetTemperature', $this->Translate('Target Temperature'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'SUFFIX'       => ' °C'
        ], 8);
        $this->EnableAction('TargetTemperature');

        $this->RegisterVariableInteger('Temperature', $this->Translate('Temperature'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
            'SUFFIX'       => ' °C'
        ], 9);

        // 0 = kein Fehler, größer 0 = Fehlerzustand der Pumpe
        $this->RegisterVariableInteger('ErrorCode', $this->Translate('Error Code'), [
            'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION
        ], 10);
    }

    public function ApplyChanges(): void
    {
        //Never delete this line!
        parent::ApplyChanges();

        $MQTTTopic = trim($this->ReadPropertyString('MQTTTopic'));
        if ($MQTTTopic === '') {
            // Ohne Topic darf nichts durchgelassen werden
            $this->SetReceiveDataFilter('^$');
            $this->SetStatus(self::STATUS_TOPIC_MISSING);
            return;
        }

        // Im JSON-Datenpaket ist "/" als "\/" maskiert, deshalb erst JSON-kodieren, dann als RegEx maskieren
        $this->SetReceiveDataFilter('.*' . preg_quote(substr(json_encode($MQTTTopic), 1, -1), '/') . '.*');
        $this->SetStatus(IS_ACTIVE);
    }

    public function RequestAction(string $Ident, mixed $Value): void
    {
        if (!array_key_exists($Ident, self::COMMANDS)) {
            $this->SendDebug(__FUNCTION__, 'Keine Aktion für Ident ' . $Ident, 0);
            return;
        }

        $Payload = [
            'CMD'   => self::COMMANDS[$Ident],
            'VALUE' => (int) $Value
        ];
        $this->sendMQTT($this->ReadPropertyString('MQTTTopic') . '/command', json_encode($Payload));
    }

    public function ReceiveData(string $JSONString): string
    {
        $Buffer = json_decode($JSONString, true);
        if (!is_array($Buffer) || !isset($Buffer['Topic'], $Buffer['Payload'])) {
            return '';
        }
        if ($Buffer['Topic'] !== $this->ReadPropertyString('MQTTTopic') . '/message') {
            return '';
        }

        $this->SendDebug(__FUNCTION__, (string) $Buffer['Payload'], 0);
        $Payload = json_decode((string) $Buffer['Payload'], true);
        if (!is_array($Payload)) {
            $this->SendDebug(__FUNCTION__, 'Payload ist kein gültiges JSON', 0);
            return '';
        }

        $Booleans = [
            'Lock'    => 'LCK',
            'Power'   => 'PWR',
            'Air'     => 'AIR',
            'Pump'    => 'FLT'
        ];
        foreach ($Booleans as $Ident => $Key) {
            if (array_key_exists($Key, $Payload)) {
                $this->SetValue($Ident, (bool) $Payload[$Key]);
            }
        }

        // RED: Heizung aktiv, GRN: Zieltemperatur erreicht. Leuchtet keines von beiden, ist die Heizung aus.
        if (array_key_exists('GRN', $Payload) && array_key_exists('RED', $Payload)) {
            $Red = (bool) $Payload['RED'];
            $Green = (bool) $Payload['GRN'];
            $this->SetValue('Heat', $Red || $Green);
            // Die Heizung heizt nur bei laufender Filterpumpe (Pump wurde oben bereits aktualisiert)
            if ($Red) {
                $State = $this->GetValue('Pump') ? self::HEAT_HEATING : self::HEAT_WAITING_FOR_PUMP;
            } else {
                $State = $Green ? self::HEAT_TARGET_REACHED : self::HEAT_OFF;
            }
            $this->SetValue('HeatState', $State);
        }

        if (array_key_exists('TGT', $Payload)) {
            $this->SetValue('TargetTemperature', (int) round((float) $Payload['TGT']));
        }
        if (array_key_exists('TMP', $Payload)) {
            $this->SetValue('Temperature', (int) round((float) $Payload['TMP']));
        }
        if (array_key_exists('ERR', $Payload)) {
            $this->SetValue('ErrorCode', (int) $Payload['ERR']);
        }
        return '';
    }
}
