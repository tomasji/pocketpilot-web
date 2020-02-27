import { createStaticWaypoint } from './WaypointFactory'
import { LatLngBounds, Polyline } from 'leaflet'

class StaticTrack {
	constructor(map, latlngs) {
		this.map = map
		if (latlngs) {
			const wpts = []
			latlngs.forEach(latlng => {
				wpts.push(this.addWaypoint(latlng))
			})
			this._createConnection(wpts).addTo(this.map)
			map.fitBounds(new LatLngBounds(latlngs))
		}
	}
	addWaypoint(latlng) {
		const wp = createStaticWaypoint(latlng)
		wp.addTo(this.map)
		return wp
	}
	_createConnection(wpts) {
		return new Polyline(wpts.map(wp => wp.getLatLng()), { color: '#FF00FF', weight: 4 })
	}
}
export { StaticTrack }
