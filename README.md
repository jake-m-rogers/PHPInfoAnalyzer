# PHP Info Analyzer (PHPIA)

PHPIA is currently taking baby steps, it will currently determine the following;

- PHP version, search for related CVE's
- Output disabled_functions and disabled_classes
- Check to see if mod_security is enabled
- Check to see if Suhosin is enabled - *This check will perform a very basic WAF analysis due to Suhosin's ability to become transparent, results are not to be 100% trusted*
- Output PHP's memory limit
- Check for path disclosure
- Reverse lookup on the IP to try and find all associated domains both through CRUSH* and BING**

- And it outputs all of this into a very pretty html page with lovely jQuery thrown on top

## Roadmap

0.1 - Initial release

## License

[GNU Copyleft License](http://www.gnu.org/copyleft/ "GNU Copyleft License") - You are free to modify and dsitribute this software whilst preserving all original references to the author.

#### Notes

CRUSH is a third party service not owned or maintained by the author

BING is a third party service not owned or maintained by the author