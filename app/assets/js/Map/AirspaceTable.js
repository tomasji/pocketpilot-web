import { DomUtil } from 'leaflet'
import naja from 'naja'

class AirspaceTable {
  constructor(track) {
    this.track = track
    this.table = this.renderEmpty()
  }
  renderEmpty() {
    const wrapper = document.getElementById('airspace').querySelector('.info-table-wrapper')
    const table = DomUtil.create('table', 'info-table airspace')
    wrapper.insertBefore(table, wrapper.children[0])
    const header = DomUtil.create('tr', 'info-table-header', table)
    DomUtil.create('th', '', header).innerText = 'TYPE'
    DomUtil.create('th', '', header).innerText = 'NAME'
    DomUtil.create('th', '', header).innerText = 'LOWER'
    DomUtil.create('th', '', header).innerText = 'UPPER'
    return table
  }
  invalidate() {
    const path = this._formatCoordinates(this.track.getWaypoints())
    naja.makeRequest('GET', `/api/v1/airspace?path=${path}`, null, {
      history: false
    })
      .then((v) => {
        this.table.parentElement.removeChild(this.table)
        this.table = this.renderEmpty()
        v.forEach((as) => {
          const row = DomUtil.create('tr', null)
          const type = DomUtil.create('td', null, row)
          const name = DomUtil.create('td', null, row)
          const from = DomUtil.create('td', null, row)
          const to = DomUtil.create('td', null, row)
          type.innerText = as.type
          name.innerText = as.name
          from.innerText = `${as.bounds.lowerDatum} ${as.bounds.lower}`
          to.innerText = `${as.bounds.upperDatum} ${as.bounds.upper}`
          this.table.appendChild(row)
        })
      })
      .catch(e => {
        console.log(e)
      })
  }

  _formatCoordinates(wpts) {
    const coordinates = []
    wpts.forEach((wpt) => {
      coordinates.push(`${wpt.getLatLng().lat},${wpt.getLatLng().lng}`)
    })
    if (coordinates.length < 2) {
      coordinates.push(coordinates[0])
    }
    return coordinates.join('|')
  }
}

export { AirspaceTable }
