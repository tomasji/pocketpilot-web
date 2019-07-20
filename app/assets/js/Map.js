import { GeoJSON, Map, TileLayer, DomUtil } from 'leaflet'
import { Track } from './Track'
import M from 'materialize-css'

const MAP_BASE = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
const MAP_OFM = 'https://snapshots.openflightmaps.org/live/1904/tiles/world/noninteractive/epsg3857/aero/512/latest/{z}/{x}/{y}.png'

const map = new Map(
	'map',
	{ minZoom: 8, maxZoom: 11 }
)
const osmLink = '<a href="http://openstreetmap.org" target="_blank">OpenStreetMap</a>'
const ground = new TileLayer(
	MAP_BASE, {
		attribution: '&copy; ' + osmLink + ' Contributors',
		maxZoom: 11
	})
const ofmLink = '<a href="https://www.openflightmaps.org/" target="_blank">OpenFlightMaps</a>'
const ofm = new TileLayer(MAP_OFM, {
	attribution: ofmLink,
	maxZoom: 11
})
map.setView([50.075, 14.437], 8)
map.zoomControl.setPosition('topright')
ground.addTo(map)
ofm.addTo(map)

let zoom = map.getZoom()
DomUtil.addClass(map.getContainer(), 'zoom-' + zoom)
map.on('zoomend', function() {
	DomUtil.removeClass(map.getContainer(), 'zoom-' + zoom)
	zoom = map.getZoom()
	DomUtil.addClass(map.getContainer(), 'zoom-' + zoom)
})

if (map.getContainer().dataset.track) {
	const geoJSON = new GeoJSON(JSON.parse(map.getContainer().dataset.track))
	new Track(map, geoJSON.getLayers()[0].feature.geometry.coordinates)
} else {
	const addFirstWpt = function(e) {
		const track = new Track(map)
		track.addWaypoint(e.latlng)
		map.off('click')
	}
	map.on('click', addFirstWpt)
}
