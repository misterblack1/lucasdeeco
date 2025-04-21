<?php

    /*
        SCRAMBLING CORE (ADDRESS TRANSLATION)
    */

    function rom_addr_cpu2rom_translate($ca) {

        // extract address lines to binary representation
        $a = array();
        for($i=0;$i<16;$i++) $a[$i] = ($ca >> $i) & 1;

        // rom address
        $r = array();

        // dynamic address shuffling changes by each 32B block, thus key is A[7:5]
        $key = array();
        $key[2] = $a[7];
        $key[1] = $a[6];
        $key[0] = $a[5];

        // each KEY signal controls its MUX2BOX unit (indexed as L,M,H by key weight)
        // ( the MUX2BOX has 2 inputs and 2 outputs, and the KEY input which selects
        //   if the data is passed straight or exchanged )

        // lsb address bit 0 specifies LO/HI
        // that passess directly (or is ignored), 16-bit system
        $r[0] = $a[0];

        // MUX2BOX_M: dynamic key MID ~ by A[6]
        $r[1] = $key[1] ? $a[5] : $a[4];
        $r[2] = $key[1] ? $a[4] : $a[5];

        // MUX2BOX_H: dynamic key MSB ~ by A[7]
        $r[3] = $key[2] ? $a[1] : $a[6];
        $r[4] = $key[2] ? $a[6] : $a[1];

        // msb address bit 7 put into the "middle" of the scrambler (static)
        $r[5] = $a[7];

        // MUX2BOX_L: dynamic key LSB ~ by A[5]
        $r[6] = $key[0] ? $a[3] : $a[2];
        $r[7] = $key[0] ? $a[2] : $a[3];

        // upper addresses are same
        for($u=8;$u<16;$u++) $r[$u] = $a[$u];

        // compose read address
        $ra = 0;
        for($i=0;$i<16;$i++) $ra += $r[$i] ? (1 << $i) : 0;

        return $ra;
    }

?>
