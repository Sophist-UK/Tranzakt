# tranzakt
Tranzakt is (for the moment at least) a Proof of Concept for a new web database application tool.

Inspired by Fabrik, a Joomla extension, Tranzakt is intended to provide:

* A web-based development environment for web-based SQL-based data applications
* A stand-alone run-time environment that is intended to be interfaced with main web sites based on CMSes like Joomla running in a separate webserver instance.

## Target Functionality

* Lists (tabular presentation of data - like rows in a table)
* Forms (a single database record - possibly with sub-rows)
* A range of data-field types (limited scope for the proof of concept) 
* Integration with Joomla inc. integration with Joomla security (not part of proof of concept which will be entirely stand-alone)

## Technology

This PoC is intended to be based on PHP for the server environment. 
If this turns out to be lacking in functionality, then a second attempt may be made using Python.

The reasons for delivering the run-time environment in a separate webserver instance are:

* To decouple the Tranzakt code from the CMS code, 
reducing the codependencies to an absolute minimum and minimising the impact of CMS changes
(e.g. those created by Joomla 2->3 and 3->4) on the Tranzakt codebase.
Additionally by doing this we enable the same code-base to be interfaced with several different CMSes
(e.g. Joomla and Wordpress) broadening the user base and making the ongoing maintenance more viable.
* To enable it to take advantage of leading server and client frameworks that will 
massively simplify the amount of code that needs to be written,
but which would likely be incompatible with the CMS.
* To enable the Tranzakt code to run in parallel with the CMS web page generation code
* To enable it to use asyncronous and multi-tasking techniques to improve performance and response times

The first attempt at a Proof of Concept will be based on the following other open-source technologies:

* Laravel, which is itself based on...
* Symfony (a lower level framework)
* Doctrine (an Object Relational Mapper)
* React.js
* Vue.js

As development proceeds, we will likely add more technologies.
