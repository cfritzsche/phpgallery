# phpgallery
Simple and Crude PHP Gallery Script

I've been using this gallery script (largely based on TStarGallery 1.0) for small private sites for ages now. Lately I have updated it a little to comply with PHP5 without notices.

It's not sophisticated but it does its job:
- navigating to folder hierarchies
- paging with configurable page size (or turn off paging completely)
- automatic thumb creation

The main file here is gallery.php. It reads all files found in $gallery_path/$full_images (as defined in config.php, default: ./gallery/full/) and displays them in a table (row and column count in config.php). Thumbs are automatically created and stored in $gallery_path/thumb. You can navigate through folder hierarchies also.
Pictures are displayed in picture.php. You probably need to adapt gallery.php and picture.php to fit into your site. They don't contain any html headers.
If you don't want to use paging in gallery.php, just call it with parameter all=true.

The scripts are free to use, without any guarantee of course.
