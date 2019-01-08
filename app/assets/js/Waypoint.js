import { Marker, CRS } from 'leaflet'
import leafletControlGeocoder from 'leaflet-control-geocoder'

class Waypoint extends Marker {
	constructor(latlng, options) {
		super(latlng, options)
		this.geocoder = leafletControlGeocoder.L.Control.Geocoder.nominatim()
	}
	fetchPlace() {
		return new Promise((resolve) => {
			this.geocoder.reverse(this.getLatLng(), CRS.scale(12), resolve)
		})
	}
}

export { Waypoint }
