<?php

namespace App\Models\Simrs\Patient;

class CreateAppointment
{
    public string $serviceUnitID;
    public string $paramedicID;
    public string $appointmentDate;
    public string $appointmentTime;
    public string $patientID;
    public string $firstName;
    public ?string $middleName;
    public ?string $lastName;
    public string $dateOfBirth;
    public string $sex;
    public ?string $streetName;
    public string $email;
    public string $guarantorID;
    public ?string $district;
    public ?string $county;
    public ?string $city;
    public ?string $state;
    public ?string $zipCode;
    public ?string $phoneNo;
    public ?string $notes;
    public ?string $birthPlace;
    public ?string $ssn;
    public ?string $mobilePhoneNo;

    /**
     * @param string $serviceUnitID
     * @param string $paramedicID
     * @param string $appointmentDate
     * @param string $appointmentTime
     * @param string $patientID
     * @param string $firstName
     * @param string|null $middleName
     * @param string|null $lastName
     * @param string $dateOfBirth
     * @param string $sex
     * @param string|null $streetName
     * @param string $email
     * @param string $guarantorID
     * @param string|null $district
     * @param string|null $county
     * @param string|null $city
     * @param string|null $state
     * @param string|null $zipCode
     * @param string|null $phoneNo
     * @param string|null $notes
     * @param string|null $birthPlace
     * @param string|null $ssn
     * @param string|null $mobilePhoneNo
     */
    public function __construct(string $serviceUnitID, string $paramedicID,
                                string $appointmentDate, string $appointmentTime,
                                string $patientID, string $firstName,
                                ?string $middleName, ?string $lastName,
                                string $dateOfBirth, string $sex, ?string $streetName,
                                string $email, string $guarantorID, ?string $district,
                                ?string $county, ?string $city, ?string $state,
                                ?string $zipCode, ?string $phoneNo, ?string $notes,
                                ?string $birthPlace, ?string $ssn, ?string $mobilePhoneNo)
    {
        $this->serviceUnitID = $serviceUnitID;
        $this->paramedicID = $paramedicID;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTime = $appointmentTime;
        $this->patientID = $patientID;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->dateOfBirth = $dateOfBirth;
        $this->sex = $sex;
        $this->streetName = $streetName;
        $this->email = $email;
        $this->guarantorID = $guarantorID;
        $this->district = $district;
        $this->county = $county;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->phoneNo = $phoneNo;
        $this->notes = $notes;
        $this->birthPlace = $birthPlace;
        $this->ssn = $ssn;
        $this->mobilePhoneNo = $mobilePhoneNo;
    }
}
