import { GeoJSON, Map, TileLayer, DomUtil } from 'leaflet'
import { Track } from './Track'
import { StaticTrack } from './StaticTrack'

const MAP_BASE = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
const MAP_OFM = 'https://snapshots.openflightmaps.org/live/2004/tiles/world/epsg3857/aero/512/latest/{z}/{x}/{y}.png'

const configureMap = function() {
  const map = new Map(
    'map',
    { minZoom: 8, maxZoom: 12 }
  )
  const osmLink = '<a href="http://openstreetmap.org" target="_blank">OpenStreetMap</a>'
  const ground = new TileLayer(
    MAP_BASE, {
      attribution: '&copy; ' + osmLink + ' Contributors',
      maxZoom: 12
    })
  const ofmLink = '<a href="https://www.openflightmaps.org/" target="_blank">OpenFlightMaps</a>'
  const ofm = new TileLayer(MAP_OFM, {
    attribution: ofmLink,
    maxZoom: 12
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
  return map
}

export const createMap = function(editable) {
  const map = configureMap()
  if (map.getContainer().dataset.track) {
    const geoJSON = new GeoJSON(JSON.parse(map.getContainer().dataset.track))
    if (editable) {
      new Track(map, GeoJSON.coordsToLatLngs(geoJSON.getLayers()[0].feature.geometry.coordinates))
    } else {
      new StaticTrack(map, GeoJSON.coordsToLatLngs(new GeoJSON(JSON.parse(map.getContainer().dataset.track)).getLayers()[0].feature.geometry.coordinates))
    }
  } else {
    if (editable) {
      const track = new Track(map)
      const addFirstWpt = function(e) {
        track.addWaypoint(e.latlng)
        map.off('click')
      }
      map.on('click', addFirstWpt)
    }
  }
}
