# ROM scrambler/descrambler

This tool can be used to scramble or descramble the contents of a Lucs Deeco ROM image.


## Building

Just run make.

```
$ make
gcc -o make_lookup_tables make_lookup_tables.c
./make_lookup_tables > lookup_tables.h
gcc -o lucas_deeco_rom lucas_deeco_rom.c
```


## Running

To descramble an image, run `lucas_deeco_rom -d [input_file [output_file]]`.

To scramble an unscrambled image, run `lucas_deeco_rom [input_file [output_file]]`.

If `input_file` is not given or when `input_file` is `-`, read from standard input.\
If `output_file` is not given or when `output_file` is `-`, write to standard output.
