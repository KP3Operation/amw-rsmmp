<?php

namespace App\Services\SimrsService\DoctorService;

use App\Dto\SimrsDto\Doctor\AppointmentDataDto;
use App\Dto\SimrsDto\Doctor\AppointmentDetailDataDto;
use App\Dto\SimrsDto\Doctor\DoctorDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeByPaymentDateDataDto;
use App\Dto\SimrsDto\Doctor\DoctorFeeByTrxDateDataDto;
use App\Dto\SimrsDto\Doctor\DoctorSummaryFeeDataDto;
use App\Dto\SimrsDto\Doctor\InpatientListDataDto;
use App\Dto\SimrsDto\Doctor\PatientRegistrationCPPTDataDto;
use App\Dto\SimrsDto\Doctor\InpatientRoomListDataDto;
use App\Exceptions\SimrsException;
use App\Services\SimrsService\ISimrsBaseApi;

class DoctorService implements IDoctorService
{
    private ISimrsBaseApi $simrsBaseApi;

    public function __construct(ISimrsBaseApi $simrsBaseApi)
    {
        $this->simrsBaseApi = $simrsBaseApi;
    }

    /**
     * @throws SimrsException
     */
    public function getDoctors(string $doctorId): DoctorDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/ParamedicGetList', [], [
            'ParamedicID' => $doctorId,
            'ParamedicName' => '',
            'SmfID' => '',
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return DoctorDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getOverviewSummaryFee(string $paramedicId, string $startDate, string $endDate): DoctorSummaryFeeDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS.asmx/ParamedicFeeSummaryByParamedicIdTransDate', [], [
            'ParamedicID' => $paramedicId,
            'TransactionDateStart' => convert_date_to_req_param($startDate),
            'TransactionDateEnd' => convert_date_to_req_param($endDate),
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return DoctorSummaryFeeDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getFeeByTrxDate(string $paramedicId, string $startDate, string $endDate): DoctorFeeByTrxDateDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS.asmx/ParamedicFeeByParamedicIDTransDate', [], [
            'ParamedicID' => $paramedicId,
            'TransactionDateStart' => convert_date_to_req_param($startDate),
            'TransactionDateEnd' => convert_date_to_req_param($endDate),
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return DoctorFeeByTrxDateDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getFeeByPaymentDate(string $paramedicId, string $paymentDateStart, string $PaymentDateEnd): DoctorFeeByPaymentDateDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS.asmx/ParamedicFeeByParamedicIDPaymentDate', [], [
            'ParamedicID' => $paramedicId,
            'PaymentDateStart' => convert_date_to_req_param($paymentDateStart),
            'PaymentDateEnd' => convert_date_to_req_param($PaymentDateEnd),
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return DoctorFeeByPaymentDateDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getInpatientList(string $paramedicId, string $roomName, int $count = 10): InpatientListDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/RegistrationGetListIpByParamedicID', [], [
            'GuarantorID' => '',
            'ParamedicID' => $paramedicId,
            'ClassID' => '',
            'RoomID' => $roomName,
            'EwsStatus' => '',
            'RecordCount' => $count,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return InpatientListDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getPatientRegistrationCPPT(string $registrationNo): PatientRegistrationCPPTDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS.asmx/RegistrationCPPT', [], [
            'RegistrationNo' => $registrationNo,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return PatientRegistrationCPPTDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getAppointments(string $paramedicId, string $appointmentDate): AppointmentDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/AppointmentGetListByParamedicIDAppointmentDate', [], [
            'ParamedicID' => $paramedicId,
            'AppointmentDate' => $appointmentDate,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return AppointmentDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getAppointmentDetail(string $appointmentNo): AppointmentDetailDataDto
    {
        $response = $this->simrsBaseApi->get('/V1_1/AppointmentWS.asmx/AppointmentGetOne', [], [
            'AppointmentNo' => $appointmentNo,
        ]);

        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return AppointmentDetailDataDto::from($data);
    }

    /**
     * @throws SimrsException
     */
    public function getInpatientRooms(): InpatientRoomListDataDto
    {
        $response = $this->simrsBaseApi->get('/MobileWS2.asmx/ServiceRoomInpatientGetList', [], [
            'RoomID' => '',
            'RoomName' => '',
        ]);

        //return $response;
        if (! $response->successful()) {
            throw new SimrsException('Gagal terhubung dengan SIMRS, mohon menghubungi tim support kami', 500);
        }

        $data = $response->json();

        return InpatientRoomListDataDto::from($data);
    }
}
