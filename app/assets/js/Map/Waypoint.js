import { Marker, CRS } from 'leaflet'
import 'leaflet-control-geocoder'
import naja from 'naja'

class Waypoint extends Marker {
  constructor(latlng, options) {
    super(latlng, options)
    this.geocoder = L.Control.Geocoder.nominatim()
  }
  fetchAirfield() {
    return naja.makeRequest('GET', '/api/v1/poi', { lat: this.getLatLng().lat, lng: this.getLatLng().lng }, { history: false })
  }
  fetchPlace() {
    return new Promise((resolve) => {
      this.geocoder.reverse(this.getLatLng(), CRS.scale(12), resolve)
    })
  }
}

export { Waypoint }
