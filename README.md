# PngCleaner

Strip provided private chunks from PNG file.
Just run the "clean" static method.
This method accepts next params:

- source PNG file
- destination PNG file
- list of chunk types to remove

ex. PngCleanr::clean('/home/user/Documents/huge.png', '/home/user/Documents/clean.png', ['mkBS']);
