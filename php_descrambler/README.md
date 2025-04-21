# Descrambler

Take the original dumps from the upper level directory and ouput a merged ROM file (debug) and a CPU view of the contents (final):

```
./rom_descramble.php
```

# Re-scrambler

Prepare a NEW file to be scrambled:

```
cp dec_CPU_64K.bin new_CPU_64K.bin
```

then run the scrambler which will output a ROM file (debug) and two halves for physical EPROM chips (final):

```
./rom_rescramble.php
```

