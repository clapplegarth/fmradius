fmradius
========

**fmradius** is a small app to overlay U.S. FM radio stations, and their approximate radius, on a Google Map.


Project Plan
============

The following is a loose plan on how the project will proceed:

1. **Research**
    - Get information on how to calculate FM radio signal radius.  An algorithm that estimates the mileage based on a station's watt output and HAAT (height above average terrain) might be needed.  Topography should not factor into this until the stretch goal phase.
    - ✓ Browse for services that this application could compete or interfere with.  _Most services that locate radio stations are not quick map-based station lookup services.  They're generally static searches that pull up tables of data and custom GIS renders of single stations._
    - Browse FCC RFCs to learn more about the FM radio band in general: its general operating parameters, like minimum and maximum power and range.
    - ✓ Find a source of data that provides information about basic station call signs, coordinates, and power.  _The FCC's data query for FM radio stations can return a tabulated set of stations that contains this information, as well as some useful metadata.  Additionally, the FCC has a list of available contours for stations at https://www.fcc.gov/encyclopedia/fm-service-contour-data-points that can be rendered using a polygon shape on a Google map!_
    - Begin reading up on Google Maps' JS API.
    - If possible, consult with someone with radio and/or GIS knowledge.

2. **Preparation**
    - ✓ Secure name and domain name. _Registered [`fmradi.us`](http://fmradi.us) ... This domain helps establish that this is a U.S.-only service (for now).  Also, `.io` is an expensive ccTLD..._
    - Set up a database for use with the service.  Because this code will be available on GitHub, the database credentials cannot be stored in the source, but should be available in an arbitrary location server-side and included.
    - ✓ Secure hosting with MySQL. _I'll be using [NearlyFreeSpeech](http://nearlyfreespeech.net/), because it's cheap, low-traffic hosting._

3. **Map Mockup**
    - Create a quick thumbnail of the site and its various views.
    - Add the main Google Maps view (takes up most of the page).
    - Add the small box in the upper-left with the logo and location search field.
    - Add a few markers, just dummy elements.
    - Add a popup box to view information about these elements when clicked, tapped, etc.
    - Add some shims for acceptable mobile display.  The "pop-up box" could span the whole screen on a small (phone) display, so it can be readable as a data sheet.
    - Have the location box actually move the map to the location entered by the user.

4. **Database**
    - Convert a source of data for use with the maps.  Make this conversion reproducible, and establish a standard format for use with the application.
    - Read and query data from this database based on a given area or range.  The data should be returned as a table.
    - Have an AJAX helper, like Oboe.js, load this data asynchronously.  Still as a table, for this step.
    - Add functions to search the database based on certain criterion.
    - Based on your earlier algorithm, create a field in the station table to store its approximate range.  This is the "max range" that will be used to quickly determine whether a marker should be rendered at a given level.


5.  **Map Data**
    - Begin to change the makers from arbitrary data to data from the database.
    - Load coordinates from the database as blind markers on the map.
    - Represent some data on each marker; have the pop-up box on each marker list facts about the station.
    - Draw dummy radii around each marker to indicate its approximate range, based on the dummy "max range" algorithm you developed earlier.
    - Find a way to have the map report its approximate bounds to the script that searches the database for stations.  Make sure that it includes a sizable margin to accommodate the maximum range of off-screen markers!  Then, cull the markers that do not appear in this radius.

6. **Streamline**
    - After the previous goals are done, begin to optimize mobile performance.  Make sure that the site is tap-friendly.  Ensure that the map view can easily support the user choosing their location.
    - Ensure cross-browser compatibility and validation.  Make sure meta-data is complete.
    - Take steps to reduce load times.
    - The map should start out zoomed close to the user's location.  This could be geolocation-based, and since that usually results in the ISP's location, a multi-state level of zoom should be chosen by default.

7. **Stretch Goals**
    - Remember that this project only spans one semester.  The object is to make a solid, reliable, simple but elegant app in that period of time.
    - Have the location entry accept other station data, especially call signs.  Have it accept the first four letters of the call sign if it's a valid sign.  
    - Rather than generating the map for the whole US, there should be a static render of this map appear when the user first enters the site.  Clicking this map will record the click location on the image element, and load the "real" map, and zoom to that location.  This will prevent excessive database and network traffic.
    - Topography affecting signal radius.  Any displacement over the HAAT should reduce the signal radius significantly, as FM stations are line-of-sight.  This section should provide enough of a stretch goal.  The FCC has a resource at https://www.fcc.gov/encyclopedia/fm-service-contour-data-points to make this much easier.
    - Using Wikipedia to pull extended data, such as a station's name, motto, streaming URL, and other semantic data about each transmitter.  This could be a one-shot or cron job script to crawl Wikipedia slowly to grab and update the stream URLs.
    - A "what's this mean?" option near the call sign, to provide information about how FM call signs work.
    - Even further on the previous idea, an embedded stream player could appear on the data page to immediately listen in to the station.
    - Other types of radio transmitters, like AM, could be represented on the map.
