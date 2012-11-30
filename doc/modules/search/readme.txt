Copyright 2012 (C) Xeno Innovations, Inc.

Created by:   Damian Suess
Created Date: 2012-11-26
Title:        Search Engine Module
Description:
  This module is intended to be a simple search engine for the attached
  modules. Freetext search will be attached to only configured modules
  in the search engine admin panel.


Inputs:
  * http://xenoPMT/search/?mod=<mod-name>q=<search-text>
  * http://xenoPMT/search/<mod-name>/<search-text>
  
  Example:
  * http://xenoPMT/search/wiki/Product+Implementation+Plan

Output:
  * Returns XML output of search results
  * Using XSL to output search engine data to be neatly formatted.

Admin:
  1.  Configure search engine to work with the specified modules
  2.  Tell SearchEngine which tables to spider and how often
  3.  Tell SearchEngine what kind of XML data to return so the
      xsl config sheet can display it nicely.

User-Priv:
  * SEARCH_ADMIN
  * SEARCH_VIEW

Sample Output:

<searchdata>
  <ret
    id="1"
    title="Sample Search Result Item - 1"
    author="Damian Suess"
    url="//link/to/data"
    rank="10"
    created="YYYY-MM-DD hh:mm:ss"
    modified="YYYY-MM-DD hh:mm:ss"
  >
    <data>
      <?[CDATA[
      BLAH BLAH BLAH BLAH
      ]]?
    </data>
  </ret>
  <ret>
    <!-- ... -->
  </ret>
</searchdata>
