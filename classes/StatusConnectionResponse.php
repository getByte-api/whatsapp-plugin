<?php

namespace GetByte\Whatsapp\Classes;

class StatusConnectionResponse
{
    protected $qrcode;

    protected $pairingCode;

    protected $status = 'DISCONNECTED';

    public function getStatus()
    {
        return $this->status;
    }

    public function getQrCode()
    {
        return $this->qrcode;
    }

    public function setQrCode($qrcode = null)
    {
        $this->qrcode = $qrcode;
    }

    public function setpairingCode($pairingCode = null)
    {
        $this->pairingCode = $pairingCode;
    }

    public function setStatus($status = null)
    {
        switch (strtoupper($status)) {
            case 'OPEN':
                $this->status = 'CONNECTED';
                break;
            case 'CLOSE':
                $this->status = 'DISCONNECTED';
                break;
            default:
                $this->status = strtoupper($status);
        }
    }

    public function toArray()
    {
        return [
            'status'     => $this->status,
            'qrcode'     => $this->qrcode,
            'pairingCode' => $this->pairingCode
        ];
    }
}
