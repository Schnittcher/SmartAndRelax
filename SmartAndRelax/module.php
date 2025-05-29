<?php

declare(strict_types=1);

require_once __DIR__ . '/../libs/MQTTHelper.php';

	class SmartAndRelax extends IPSModule
	{
		use MQTTHelper;

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			$this->ConnectParent('{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}');
            $this->RegisterPropertyString('MQTTTopic', 'layzspa');

			//Register Variables

			$this->RegisterVariableBoolean('Lock', $this->Translate('Lock'), [
                'PRESENTATION'    => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
                'OPTIONS'         => json_encode([
                    [
                        'Value'            => true,
                        'Caption'          => $this->Translate('Locked'),
                        'IconActive'       => false,
                        'Icon'             => 'Information',
                        'ColorActive'      => true,
                        'ColorValue'       => 65280
                    ],
                    [
                        'Value'            => false,
                        'Caption'          => $this->Translate('Unlocked'),
                        'IconActive'       => false,
                        'Icon'             => 'Information',
                        'ColorActive'      => true,
                        'ColorValue'       => 16711680,
                    ],
                ]
                    )
            ], 0);
			$this->EnableAction('Lock');

			$this->RegisterVariableBoolean('Power', $this->Translate('Power'), [
                'PRESENTATION'    => VARIABLE_PRESENTATION_SWITCH
            ], 1);
			$this->EnableAction('Power');

			//Unitstate

			$this->RegisterVariableBoolean('Air', $this->Translate('Air'), [
                'PRESENTATION'    => VARIABLE_PRESENTATION_SWITCH
            ], 3);
			$this->EnableAction('Air');

			$this->RegisterVariableBoolean('HeatGrn', $this->Translate('Heat GRN'), [
                'PRESENTATION'    => VARIABLE_PRESENTATION_SWITCH
            ], 4);
			$this->EnableAction('HeatGrn');

			$this->RegisterVariableBoolean('HeatRed', $this->Translate('Heat Red'), [
                'PRESENTATION'    => VARIABLE_PRESENTATION_SWITCH
            ], 5);
			$this->EnableAction('HeatRed');

			$this->RegisterVariableBoolean('Pump', $this->Translate('Pump'), [
                'PRESENTATION'    => VARIABLE_PRESENTATION_SWITCH
            ], 6);
			$this->EnableAction('Pump');

			$this->RegisterVariableFloat('TargetTemperature', $this->Translate('Target Temperature'), [
                    'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
                    'SUFFIX'       => ' °C'
            ], 7);
			$this->EnableAction('TargetTemperature');

			$this->RegisterVariableFloat('Temperature', $this->Translate('Temperature'), [
				'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
				'SUFFIX'       => ' °C'
		], 8);
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
			$MQTTTopic = $this->ReadPropertyString('MQTTTopic');
            $this->SetReceiveDataFilter('.*' . $MQTTTopic . '.*');
		}

		public function RequestAction($Ident, $Value)
        {
			$MQTTTopic = $this->ReadPropertyString('MQTTTopic');
			switch ($Ident) {
				case 'Air':
					$Payload['CMD'] = 2;
					$Payload['VALUE'] = intval($Value);
					$this->sendMQTT($MQTTTopic.'/command', json_encode($Payload));
					break;
				case 'HeatGrn':
					$Payload['CMD'] = 3;
					$Payload['VALUE'] = intval($Value);
					$this->sendMQTT($MQTTTopic.'/command', json_encode($Payload));
					break;
				case 'Pump':
					$Payload['CMD'] = 4;
					$Payload['VALUE'] = intval($Value);
					$this->sendMQTT($MQTTTopic.'/command', json_encode($Payload));
					break;
				case 'TargetTemperature':
					$Payload['CMD'] = 0;
					$Payload['VALUE'] = intval($Value);
					$this->sendMQTT($MQTTTopic.'/command', json_encode($Payload));
					break;
				default:
					# code...
					break;
			}

		}

        public function ReceiveData($JSONString)
        {
            $Buffer = json_decode($JSONString, true);
			$MQTTTopic = $this->ReadPropertyString('MQTTTopic');

            $Payload = json_decode($Buffer['Payload'], true);
            if (array_key_exists('Topic', $Buffer)) {
				if (fnmatch($MQTTTopic.'/message', $Buffer['Topic'])) {
					IPS_LogMessage('test',print_r($Payload,true));

					$this->SetValue('Lock', $Payload['LCK']);
					$this->SetValue('Power', $Payload['PWR']);
					$this->SetValue('Air', $Payload['AIR']);
					$this->SetValue('HeatGrn', $Payload['GRN']);
					$this->SetValue('HeatRed', $Payload['RED']);
					$this->SetValue('Pump', $Payload['FLT']);
					$this->SetValue('TargetTemperature', $Payload['TGT']);
					$this->SetValue('Temperature', $Payload['TMP']);
				}

			}
		}

	}