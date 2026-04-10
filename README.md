# Preference Theme for ProjectSend

This repository contains the contents of `templates/preference` from a ProjectSend installation.

It is a client-facing theme package for ProjectSend, not a standalone PHP application.

## Preview

![Preference theme screenshot](./screenshot.png)

## Install

1. Download or clone this repository.
2. Create the folder `templates/preference` inside your ProjectSend installation if it does not already exist.
3. Copy the contents of this repository into `templates/preference`.
4. In ProjectSend, go to the client template settings and select `Preference`.

## Included Files

- `template.php` for the authenticated client file list
- `public.php` for public file listings
- `public-download.php` for the public single-file page
- `main.scss` and `main.min.css` for theme styling
- `js/`, `img/`, `lang/`, and bundled font assets

## Notes

- This theme depends on the host ProjectSend runtime, including shared helpers from the main application.
- The repository root matches the contents of `templates/preference`, so it should be copied into that folder in your ProjectSend instance.
- `main.min.css` is the compiled stylesheet used by the theme. `main.scss` is included for maintenance.

## Credits

- Theme adaptation by Nuno Fernandes
- Based on ProjectSend's Pinboxes template and ProjectSend's client template system
