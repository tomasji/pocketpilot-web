# Pocket Pilot
Web app for planning flights.
www.pocketpilot.cz

## Public API
/api/v1/

### Nearest POI
/poi?lng={float}&lat={float}&range={int}&hasRunway={1|0|null}
#### Parameters
##### lng
Longitude. MANDATORY
##### lat
Longitude. MANDATORY
##### range
Circle range to look in. Value in meters. Must be lower than 100000. OPTIONAL (default 500)
##### hasRunway
Look only for POI with runway/without runway/do not filter. OPTIONAL (default null)
#### Example
`/poi?lng=14.9577416667&lat=50.0591472222&range=100`

`/poi?lng=14.9577416667&lat=50.0591472222&range=10000&hasRunway=1`

### Polyline/Airspace Intersection
/airspace?path={string}
#### Parameters
##### path
Linestring path. Points (lat lng) separated by | (pipe). MANDATORY
#### Example
`/airspace?path=49.986552130506,15.00114440918|50.123608640659,13.687176381354|50.522077192267,15.032937680371`
