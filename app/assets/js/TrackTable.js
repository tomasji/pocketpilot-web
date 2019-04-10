import { DomUtil } from 'leaflet'
import { getHeading } from './Utils'

class TrackTable {
	constructor(mapContainer) {
		this.mapContainer = mapContainer
		this.table = this.renderEmpty()
	}
	renderEmpty() {
		const wrapper = document.getElementById('controls')
		const table = DomUtil.create('table', 'info-table')
		wrapper.insertBefore(table, wrapper.children[0])
		const header = DomUtil.create('tr', 'info-table-header', table)
		DomUtil.create('th', '', header).innerText = 'WPT'
		DomUtil.create('th', '', header).innerText = 'HDG'
		DomUtil.create('th', '', header).innerText = 'DIST'
		DomUtil.create('th', '', header).innerText = 'TIME'
		return table
	}
	addWaypoint(index, current, created) {
		const row = DomUtil.create('tr', '')
		const place = DomUtil.create('td', '', row)
		place.innerText = 'Loading...'
		const hdg = DomUtil.create('td', '', row)
		const dist = DomUtil.create('td', '', row)
		const time = DomUtil.create('td', '', row)
		this.table.insertBefore(row, this.table.children[index + 1])
		this._setValues(current, created, place, hdg, dist, time)
	}
	editWaypoint(index, previous, current, next) {
		const currCells = this.table.children[index + 1].children
		this._setValues(previous, current, currCells[0], currCells[1], currCells[2], currCells[3])
		if (this.table.children[index + 2]) {
			const nextCells = this.table.children[index + 2].children
			this._setValues(current, next, nextCells[0], nextCells[1], nextCells[2], nextCells[3])
		}
	}
	removeWaypoint(index, previous, next) {
		DomUtil.remove(this.table.children[index + 1])
		if (this.table.children[index + 1]) {
			const cells = this.table.children[index + 1].children
			this._setValues(previous, next, cells[0], cells[1], cells[2], cells[3])
		}
	}

	_setValues(wp1, wp2, place, hdg, dist, time) {
		if (wp2) {
			wp2.fetchPlace()
				.then((results) => {
					place.innerText = results[0].properties.address.city || results[0].properties.address.town
				})
				.catch(() => {
					place.innerText = 'Waypoint'
				})
		}
		if (wp1 && wp2) {
			const km = Math.floor(wp1.getLatLng().distanceTo(wp2.getLatLng()) / 1000)
			hdg.innerText = getHeading(wp1.getLatLng(), wp2.getLatLng())
			dist.innerText = km
			time.innerText = Math.floor(km / 150 * 60)
		} else {
			hdg.innerText = '-'
			dist.innerText = '-'
			time.innerText = '-'
		}
	}
}
export { TrackTable }
