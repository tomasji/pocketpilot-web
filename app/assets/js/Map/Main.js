import { createMap } from './Map'
import { Main } from '../Main'

function start(win) {
  const main = new Main(win)

  return {
    requireMap: () => {
      const selector = '#map'
      const apply = (el) => {
        const editable = el.dataset.editable
        switch (editable) {
          case 'true':
            createMap(true)
            break
          case 'false':
            createMap(false)
            break
          default:
            throw Error('Invalid [data-editable] value.')
        }
      }
      main.attach(selector, apply)
    }
  }
}

export const map = start(window)
