import { DomUtil } from 'leaflet'
import { getHeading } from './Utils'

class TrackTable {
  constructor() {
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
    const total = DomUtil.create('tr', 'info-table-summary', table)
    DomUtil.create('td', '', total).innerHTML = '<strong>SUM</strong>'
    DomUtil.create('td', '', total)
    DomUtil.create('td', '', total).innerText = '-'
    DomUtil.create('td', '', total).innerText = '-'
    return table
  }
  addWaypoint(index, current, created, next) {
    const row = DomUtil.create('tr', '')
    const place = DomUtil.create('td', '', row)
    place.innerText = 'Loading...'
    const hdg = DomUtil.create('td', '', row)
    const dist = DomUtil.create('td', '', row)
    const time = DomUtil.create('td', '', row)
    this.table.insertBefore(row, this.table.children[index + 1])
    this._setValues(current, created, place, hdg, dist, time)
    if (next) {
      const nextCells = this.table.children[index + 2].children
      this._setValues(created, next, nextCells[0], nextCells[1], nextCells[2], nextCells[3])
    }
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
  recalculateTimes(speed) {
    const rows = Array.from(this.table.children).slice(2)
    rows.forEach((row, index) => {
      row.children[3].innerText = Math.floor(parseInt(row.children[2].innerText) / speed * 60) + (index === rows.length - 1 ? ' min' : '')
    })
  }

  _setValues(wp1, wp2, place, hdg, dist, time) {
    if (wp2) {
      wp2.fetchAirfield()
        .then(result => {
          if (result.hasOwnProperty('name')) {
            place.innerText = result.name
          } else {
            wp2.fetchPlace()
              .then((results) => {
                place.innerText = place.innerText = results[0].properties.address.city || results[0].properties.address.town || 'Waypoint'
              })
              .catch(() => {
                place.innerText = 'Waypoint'
              })
          }
        })
    }
    if (wp1 && wp2) {
      const speed = this.table.parentElement.querySelector('.controls-speed input[type="text"][name="speed"]').value
      const km = Math.floor(wp1.getLatLng().distanceTo(wp2.getLatLng()) / 1000)
      hdg.innerText = getHeading(wp1.getLatLng(), wp2.getLatLng())
      dist.innerText = km
      time.innerText = Math.floor(km / speed * 60)
    } else {
      hdg.innerText = '-'
      dist.innerText = '-'
      time.innerText = '-'
    }
    const rows = Array.from(this.table.children).slice(2)
    rows.pop()
    const summary = this.table.children[this.table.children.length - 1].children
    let totalDist = 0
    let totalTime = 0
    rows.forEach((row) => {
      totalDist += parseInt(row.children[2].innerText)
      totalTime += parseInt(row.children[3].innerText)
    })
    summary[2].innerText = totalDist + ' km'
    summary[3].innerText = totalTime + ' min'
  }
}
export { TrackTable }
