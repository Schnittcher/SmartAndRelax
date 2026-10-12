<?php

declare(strict_types=1);

trait MQTTHelper
{
    /**
     * Sendet ein MQTT-Paket (Publish) über den übergeordneten MQTT-Client oder -Server.
     */
    protected function sendMQTT(string $Topic, string $Payload): bool
    {
        $Packet = [
            'DataID'           => '{043EA491-0325-4ADD-8FC2-A30C8EEB4D3F}',
            'PacketType'       => 3,
            'QualityOfService' => 0,
            'Retain'           => false,
            'Topic'            => $Topic,
            'Payload'          => $Payload
        ];
        $PacketJSON = json_encode($Packet);
        $this->SendDebug(__FUNCTION__, $PacketJSON, 0);

        $Result = @$this->SendDataToParent($PacketJSON);
        if ($Result === false) {
            $this->SendDebug(__FUNCTION__, 'Senden an den Parent fehlgeschlagen', 0);
            return false;
        }
        return true;
    }
}
