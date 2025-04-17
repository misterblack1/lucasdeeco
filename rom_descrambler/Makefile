lucas_deeco_rom: lucas_deeco_rom.c lookup_tables.h
	gcc -o $@ lucas_deeco_rom.c

lookup_tables.h: make_lookup_tables
	./make_lookup_tables > lookup_tables.h

make_lookup_tables: make_lookup_tables.c
	gcc -o $@ $^

clean:
	rm -f make_lookup_tables lookup_tables.h lucas_deeco_rom
