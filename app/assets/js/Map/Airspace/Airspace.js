import AirspaceChart from './AirspaceChart'
import AirspaceTable from './AirspaceTable'
import naja from 'naja'

export default class Airspace {
  constructor(track) {
    this.track = track
    this.table = new AirspaceTable()
    this.chart = new AirspaceChart()
  }
  invalidate() {
    naja.makeRequest(
      'GET',
      '/api/v1/airspace',
      {
        path: this._formatCoordinates(this.track.getWaypoints())
      },
      {
        history: false
      }
    )
      .then((data) => {
        this.table.update(data)
        this.chart.update(data)
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
