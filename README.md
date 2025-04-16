## Lucas Deeco Terminal Information 

High resolution photos and ROM dumps: [https://archive.org/details/lucas-deeco-seal-touch](https://archive.org/details/lucas-deeco-seal-touch)

CPU: 80186 with 16 bit data bus

Dumped ROMs are scrambled on address lines A0-A6. A 18CV8PC-25 PAL sits between the CPU and the ROM/RAM and appears to be there to allow the CPU to read a ROM with scrambled contents. It appears a PAL scrambles the data in 16 byte chunks. (32 bytes when you combine the two ROM HI/LOW files.) There appear to be 8 "keys" which are the different scrambled address line combinations, repeating every 256 bytes. See below:

```
CPU    74LS373             18CV8PC-25 ROM/RAM

                           1 CLK ?
                           11    ?
                           19    ?
DA0                         2 -> ?    --
DA1                         3 -> 18   A0
DA2                         4 -> 17   A1
DA3                         5 -> 16   A2
DA4                         6 -> 15   A3
DA5                         7 -> 14   A4
DA6                         8 -> 13   A5
DA7                         9 -> 12   A6
DA8    Pin 18 -> Pin 15               A7
DA9    Pin 17 -> Pin 16               A8
DA10   Pin 14 -> Pin 15               A9
DA11   Pin 13 -> Pin 12               A10
DA12   Pin 8  -> Pin 9                A11
DA13   Pin 7  -> Pin 6                A12
DA14   Pin 4  -> Pin 5                A13
DA15   Pin 3  -> Pin 2                A14

DA0 - DA15 go to a pair of 74LS245's which are then connected to D0-D15 on the RAM/ROM/EEPROMs. 
```

This means that the "key" used for the first 16 bytes of the ROMs is the same key used every 256 bytes through the entire length of the ROM. (Each chip is 32k in size, so 64k in total as this is a 16 bit system and the ROMs are low and high byte.)

Example from the start of the ROM:

A0,1,2,3,4,5,6 (native mapping as if PAL was not there, data is scrambled)
```
..) ONN .I...*..}.DIIC19M...M........ .4...&.........+`....=...#COGIRP..........PYTAOR1..............U..u............V..........RIL S 86........GHELCO-...............;..t...@u....t....40...u..T ECAT..........(CTRIO...........
```

A3,5,6,0,1,2,4 (first 32 bytes look correct, but beyond that, still scambled.)
```
..}.COPYRIGHT (C) DIGITAL ELECTRONICRPORS COATION 19..1.86-......IM..............................*M............................................T...........t...). .+.U.V.........4`.....;...........u....t40.....&.=.....................@.u.$.....#....u.......
```

A3,6,5,0,1,2,4 (next 32 bytes look correct)
```
..}.RIGHCOPYT (C) DIL ELGITAECTRONICS CORPORATION 1986-...1......IM..............................*M............................................T.......t.......). .+.....U.V.....4`.;................t40u........&.=.................@.u.....$.....#u...........
```

The complete string is `COPYRIGHT (C) DIGITAL ELECTRONICS CORPORATION 1986` but crosses the boundary so requires two keys to decode entirely.

I have included two sample files extracted from the ROM on the 256 byte boundary, 128 byte selection HI.bin and 128 byte selection LO.bin. Run them through the included `bit swapper.html` program and search for `CONFIGURATION BY STATE MEMORY`. This will give you an example of human readable text that is now visible once descrambled. 

Hitting the search button will result in all the combinations of address lines that decode that string you entered. (Scroll down once the search is done to see all matching combinations, click them to see the decoded data.)

Note: The HTML/JS code was entirely made with Google Gemini.
