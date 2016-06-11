### MassCrop ###

This is just a little personal project I undertook in order to create a timelapse of my wife's first pregnancy.
You can see the results here: https://www.youtube.com/watch?v=3MaFP0ZNKjQ

The challenge was, take one picture, everday, during the pregnancy and then put it all together in a short movie. The main thing was: how to get the pictures to line up when shown very fast? At least the eyeline should be the same for every pic.

This is where this PHP script comes in handy. You select a coordinate at each pic to do the crop. Then the script will crop all pics with the same offset to the top and to the left, generating images of the exact same size.

For best results, the original pics should have roughly the same size as well, or you'll run into resolution issues.

To run, just put the script in a directory, and the pics you wanna crop inside the "to_crop" folder. You don't need to do it all at once. There is a control file (cropped_files.txt), so the next time you load it, it will ignore the files already processed.