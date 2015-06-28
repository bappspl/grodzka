<?php
namespace Pharmacy\Model;

use CmsIr\System\Model\Model;

class Pharmacy extends Model
{
    protected $id;
    protected $name;
    protected $city;
    protected $street;
    protected $phone;
    protected $weekdays;
    protected $target;
    protected $saturday;
    protected $sunday;
    protected $zip_code;
    protected $place_id;

    //virtual

    protected $place;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->city = (!empty($data['city'])) ? $data['city'] : null;
        $this->street = (!empty($data['street'])) ? $data['street'] : null;
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : null;
        $this->weekdays = (!empty($data['weekdays'])) ? $data['weekdays'] : null;
        $this->target = (!empty($data['target'])) ? $data['target'] : null;
        $this->saturday = (!empty($data['saturday'])) ? $data['saturday'] : null;
        $this->sunday = (!empty($data['sunday'])) ? $data['sunday'] : null;
        $this->zip_code = (!empty($data['zip_code'])) ? $data['zip_code'] : null;
        $this->place_id = (!empty($data['place_id'])) ? $data['place_id'] : null;
        $this->place = (!empty($data['place'])) ? $data['place'] : null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getWeekdays()
    {
        return $this->weekdays;
    }

    /**
     * @param mixed $weekdays
     */
    public function setWeekdays($weekdays)
    {
        $this->weekdays = $weekdays;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getSunday()
    {
        return $this->sunday;
    }

    /**
     * @param mixed $sunday
     */
    public function setSunday($sunday)
    {
        $this->sunday = $sunday;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * @param mixed $zip_code
     */
    public function setZipCode($zip_code)
    {
        $this->zip_code = $zip_code;
    }

    /**
     * @return mixed
     */
    public function getPlaceId()
    {
        return $this->place_id;
    }

    /**
     * @param mixed $place_id
     */
    public function setPlaceId($place_id)
    {
        $this->place_id = $place_id;
    }

    /**
     * @return mixed
     */
    public function getSaturday()
    {
        return $this->saturday;
    }

    /**
     * @param mixed $saturday
     */
    public function setSaturday($saturday)
    {
        $this->saturday = $saturday;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

}