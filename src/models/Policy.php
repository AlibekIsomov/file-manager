<?php
class Policy {
    private $id;
    private $sery;
    private $number;
    private $end_date;
    private $uuid;

    public function __construct($id, $sery, $number, $end_date, $uuid) {
        $this->id = $id;
        $this->sery = $sery;
        $this->number = $number;
        $this->end_date = $end_date;
        $this->uuid = $uuid;
    }

    public function getId() {
        return $this->id;
    }

    public function getSery() {
        return $this->sery;
    }

    public function getNumber() {
        return $this->number;
    }

    public function getEndDate() {
        return $this->end_date;
    }

    public function getUuid() {
        return $this->uuid;
    }

    public function toArray() {
        return array(
            'id' => $this->id,
            'sery' => $this->sery,
            'number' => $this->number,
            'end_date' => $this->end_date,
            'uuid' => $this->uuid
        );
    }
}

