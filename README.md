# PHP Info Analyzer (PHPIA)

The aim of PHPIA is to speed up the process of enumeration for a penetration tester. It will currently output the information gathered into a human readable format, more will be added in the future.

It will currently perform the following tasks;

- Check PHP version, search for related CVE's
- Output disabled_functions and disabled_classes
- Check to see if a WAF is enabled - *This check will perform a very basic WAF analysis, results are not to be 100% trusted*
- Check open_basedir
- Check safe_mode

## Coming

- Reverse lookup on the IP to try and find all associated domains both through CRUSH* and BING**
- HTML output
- XML output
- Integrate waffit for better WAF detection
- Check to see if mod_security is enabled
- Check for path disclosure

## Roadmap

0.1 - Initial release

## License

[GNU Copyleft License](http://www.gnu.org/copyleft/ "GNU Copyleft License") - You are free to modify and dsitribute this software whilst preserving all original references to the author.

#### Notes

CRUSH is a third party service not owned or maintained by the author

BING is a third party service not owned or maintained by the author

Maintained by @jake_m_rogers with help from @bdpuk