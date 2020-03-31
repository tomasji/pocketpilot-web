import { GeoJSON, DomUtil } from 'leaflet'
import { Waypoint } from '../Map/Waypoint'
import { getHeading } from '../Map/Utils'
import { Main } from '../Main'
import $ from 'DOMLoaded'

function start(win) {
  const main = new Main(win)

  return {
    requireTable: () => {
      const selector = 'table[data-track]'
      const apply = (el) => {
        const tableBody = el.getElementsByTagName('tbody')[0]
        const geoJSON = new GeoJSON(JSON.parse(el.dataset.track))
        const latlngs = GeoJSON.coordsToLatLngs(geoJSON.getLayers()[0].feature.geometry.coordinates)
        const speed = document.querySelector('.speed')
        let totalTime = 0
        let totalDist = 0
        latlngs.forEach((latlng, i) => {
          const wpt = new Waypoint(latlng)
          const tr = DomUtil.create('tr', 'wpt', tableBody)
          const town = DomUtil.create('td', '', tr)
          town.colSpan = '2'
          const place = DomUtil.create('input', '', town)
          place.value = '...'
          wpt.fetchAirfield()
            .then(result => {
              if (result.hasOwnProperty('name')) {
                place.value = result.name
              } else {
                wpt.fetchPlace()
                  .then((results) => {
                    place.value = results[0].properties.address.city || results[0].properties.address.town || 'Waypoint'
                  })
                  .catch(() => {
                    place.value = 'Waypoint'
                  })
              }
            })

          if (i > 0) {
            const prevLatLng = latlngs[i - 1]
            const distance = Math.floor(prevLatLng.distanceTo(wpt.getLatLng()) / 1000)
            const time = Math.floor(distance / speed.value * 60)
            DomUtil.create('td', '', tr).innerText = getHeading(prevLatLng, wpt.getLatLng())
            DomUtil.create('td', '', tr).innerText = distance
            DomUtil.create('td', '', tr).innerText = time
            totalDist += distance
            totalTime += time
          } else {
            DomUtil.create('td', '', tr).innerText = '-'
            DomUtil.create('td', '', tr).innerText = '-'
            DomUtil.create('td', '', tr).innerText = '-'
          }
          DomUtil.create('td', '', tr)
          document.querySelector('.total-time').innerHTML = totalTime + ' min'
          document.querySelector('.total-distance').innerHTML = totalDist + ' km'
        })

        $(() => {
          speed.addEventListener('input', function() {
            if (speed.value && speed.value > 0) {
              totalTime = 0
              Array.from(tableBody.children).slice(2).forEach(row => {
                const time = Math.floor(parseInt(row.children[2].innerText) / speed.value * 60)
                row.children[3].innerText = time
                totalTime += time
              })
              document.querySelector('.total-time').innerHTML = totalTime + ' min'
            }
          })
        })
      }
      main.attach(selector, apply)
    }
  }
}

export const navlog = start(window)
