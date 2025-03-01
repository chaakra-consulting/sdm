<?php

namespace App\Helpers;

class Functions
{
    public static function generateColorForKeteranganAbsensi($keteranganSlug)
    {   
        switch($keteranganSlug){
            case"hari-kerja":
                return "rgb(100, 51, 94, 1)";
            case"wfo":
                return "rgb(238, 51, 94, 1)";
            case"wfh":
                return "rgb(1, 98, 232, 1)";
            case"sakit":
                return "rgb(0, 204, 204, 1)";
            case"ijin":
                return "rgb(72, 0, 201, 1)";
            case"lembur":
                return "rgb(59, 72, 99, 1)";
            case"alpa":
                return "rgb(95, 109, 136, 1)";
            case"ijin-direktur":
                return "rgb(29, 153, 12, 1)";
            default:
                return "rgb(241, 0, 117, 1)";
        }
    } 
    
    public static function generateUrlByRoleSlug($roleSlug)
    {   
        switch($roleSlug){
            case"admin-sdm":
                return "admin_sdm";
            case"admin":
                return "admin";
            case"manager":
                return "manajer";
            case"direktur":
                return "direktur";
            case"karyawan":
                return "karyawan";
            case"super-admin":
                return "superadmin";
        }
    }  
}