<?php

namespace App\Helpers;
use Rats\Zkteco\Lib\Zkteco;

class DeviceHelper
{
// Device Logs And Users

    // Check if the IP can connect in the PC
    public static function zkIsOnline($ip, $port = 4370, $timeout = 1)
    {
        $socket = @fsockopen($ip, $port, $errno, $errstr, $timeout);
        if ($socket) {
            fclose($socket);
            return true;
        }
        return false;
    }

    // Get all attendance in the device
    public static function device_attendance($location_name, $serialnumber, $ip){

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($ip);

            $zk->connect();
            $attendance = $zk->getAttendance();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Connected Successfully',
                'attendance' => $attendance,
                'location_name' => $location_name,
                'serialnumber' => $serialnumber,
                'bio_ip' => $ip
            ];

        }catch(\Exception $e){

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'attendance' => null,
                'location_name' => $location_name,
                'serialnumber' => $serialnumber,
                'bio_ip' => $ip
            ];
        }
    }

    // Clear all attendance
    public static function clear_device_attendance($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env('BIO_IP') : $bio_ip;

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($bio_ip);

            $zk->connect();
            $attendance = $zk->clearAttendance();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'All Attendance is cleared',
                'bio_ip' => $bio_ip
            ];

        }catch(\Exception $e){

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'bio_ip' => $bio_ip
            ];
        }
    }

    // Get the serial number of the device
    public static function device_serial_number($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env(('BIO_IP')) : $bio_ip;

        try {
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($bio_ip);

            if (!@$zk->connect()) {
                return [
                    'success' => false,
                    'message' => "Cannot connect to device at IP: {$bio_ip}",
                    'serialnumber' => null
                ];
            }

            $serialnumber = $zk->serialNumber();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Connected successfully',
                'serialnumber' => $serialnumber,
                'bio_ip' => $bio_ip,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'serialnumber' => null,
                'bio_ip' => $bio_ip,
            ];
        }
    }

    // Get the device all user
    public static function device_user($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env(('BIO_IP')) : $bio_ip;

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($bio_ip);

            $zk->connect();
            $users = $zk->getUser();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Connected successfully',
                'list_user' => $users,
                'bio_ip' => $bio_ip,
            ];

        }catch(\Exception $e){
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'list_user' => null,
                'bio_ip' => $bio_ip,
            ];
        }
    }

    // Device Control
    // Turn off the device
    public static function device_power_off($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env('BIO_IP') : $bio_ip;

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }
            $zk = new ZKTeco($bio_ip);
            $zk->connect();
            $power_off = $zk->shutdown();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Device Power Off',
                'shutdown' => $power_off,
                'bio_ip' => $bio_ip,
            ];

        }catch(\Exception $e){
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'shutdown' => null,
                'bio_ip' => $bio_ip,
            ];
        }
    }

    // Restart the device
    public static function device_restart($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env('BIO_IP') : $bio_ip;

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($bio_ip);

            $zk->connect();
            $restart = $zk->restart();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Device is Restarted',
                'restart' => $restart,
                'bio_ip' => $bio_ip,
            ];

        }catch(\Exception $e){
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'restart' => null,
                'bio_ip' => $bio_ip,
            ];
        }
    }

    // Device Test Voice
    public static function device_test_voice($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env('BIO_IP') : $bio_ip;

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($bio_ip);

            $zk->connect();
            $device_test_voice = $zk->testVoice();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Device test voice completed',
                'os_version' => $device_test_voice,
                'bio_ip' => $bio_ip,
            ];
        }catch(\Exception $e){
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'os_version' => null,
                'bio_ip' => $bio_ip,
            ];
        }
    }

    // Device Details
    // Get the device version
    public static function device_version($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env('BIO_IP') : $bio_ip;

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($bio_ip);

            $zk->connect();
            $device_version = $zk->version();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Device version successfully fetched',
                'device_version' => $device_version,
                'bio_ip' => $bio_ip,
            ];
        }catch(\Exception $e){
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'device_version' => null,
                'bio_ip' => $bio_ip,
            ];
        }
    }

    // Get the device os version
    public static function device_os_version($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env('BIO_IP') : $bio_ip;

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($bio_ip);

            $zk->connect();
            $device_os_version = $zk->osVersion();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Device OS version successfully fetched',
                'os_version' => $device_os_version,
                'bio_ip' => $bio_ip,
            ];
        }catch(\Exception $e){
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'os_version' => null,
                'bio_ip' => $bio_ip,
            ];
        }
    }

    // Get the device name
    public static function device_name($bio_ip = 'default'){
        $bio_ip = $bio_ip == 'default' ? env('BIO_IP') : $bio_ip;

        try{
            // ⛔ FAST FAIL if offline
            if (!DeviceHelper::zkIsOnline($bio_ip)) {
                throw new \Exception('Biometric device is offline');
            }

            $zk = new ZKTeco($bio_ip);

            $zk->connect();
            $device_name = $zk->deviceName();
            $zk->disconnect();

            return [
                'success' => true,
                'message' => 'Device Name Successfully Fetched',
                'device_name' => $device_name,
                'bio_ip' => $bio_ip,
            ];

        }catch(\Exception $e){
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'device_name' => null,
                'bio_ip' => $bio_ip,
            ];
        }
    }

}
