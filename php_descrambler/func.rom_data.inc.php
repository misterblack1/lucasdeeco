<?php

    /*
        FILE I/O
    */

    function rom_data_load($name) {
        return file_get_contents($name);
    }

    function rom_data_save($name,$data) {
        return file_put_contents($name,$data);
    }


    /*
        DUAL 8-bit CHIP HANDLING
    */

    function rom_data_merge($hi,$lo) {
        $halfsize = strlen($lo);

        $data = '';
        for($i=0;$i<$halfsize;$i++) {
            $data .= $lo[$i];
            $data .= $hi[$i];
        }

        return $data;
    }

    function rom_data_slice($rom,$part,$parts=2) {
        $fullsize = strlen($rom);

        $data = '';
        for($i=0;$i<$fullsize;$i+=$parts) {
            $data .= $rom[$i+$part];
        }

        return $data;
    }


    /*
        IMAGE TRANSLATION
    */

    function rom_data_decode($rom) {
        $fullsize = strlen($rom);

        // cpu: linear progression (of gathering)
        // rom: scattered reading
        $cpu = '';
        for($ca=0;$ca<$fullsize;$ca++) {
            $ra = rom_addr_cpu2rom_translate($ca);
            $cpu .= $rom[$ra];
        }

        return $cpu;
    }


    function rom_data_encode($cpu) {
        $fullsize = strlen($cpu);

        // pre-clear rom image
        // (to allow random access into PHP string)
        $rom = '';
        for($ca=0;$ca<$fullsize;$ca++) {
            $rom .= chr(0xFF);
        }

        // cpu: linear progression (of source data)
        // rom: scattered writing
        for($ca=0;$ca<$fullsize;$ca++) {
            $ra = rom_addr_cpu2rom_translate($ca);
            $rom[$ra] = $cpu[$ca];
        }

        return $rom;
    }

?>
