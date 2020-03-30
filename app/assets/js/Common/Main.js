import naja from 'naja'
import M from 'materialize-css'
import initIcons from './icons'
import { Main } from '../Main'
import { SnippetPostProcessor } from '../SnippetPostProcessor'

function start(win) {
  const snippetPostProcessor = new SnippetPostProcessor()
  const main = new Main(win, snippetPostProcessor)

  return {
    requireIcons: () => {
      initIcons(win.document)
    },
    requireNetteForms: () => {
      window.Nette = require('nette-forms')
      window.Nette.initOnLoad()
      window.Nette.showFormErrors = function(form, errors) {
        var messages = []
        var focusElem
        for (var i = 0; i < errors.length; i++) {
          var elem = errors[i].element
          var message = errors[i].message
          if (messages.indexOf(message) < 0) {
            messages.push(message)
            if (!focusElem && elem.focus) {
              focusElem = elem
            }
          }
        }
        if (messages.length) {
          messages.forEach(message => {
            M.toast({ html: message, displayLength: 2500 })
          })
          if (focusElem) {
            focusElem.focus()
          }
        }
      }
    },
    requireNaja: () => {
      naja.initialize()
      naja.formsHandler.netteForms = window.Nette
      naja.snippetHandler.addEventListener('afterUpdate', (event) => {
        snippetPostProcessor.applyCallbacksOn(event.snippet)
      })
    },
    requireSideNav: () => {
      const selector = '.sidenav'
      const apply = (el) => {
        M.Sidenav.init(el)
      }
      main.attach(selector, apply)
    },
    requireModal: () => {
      const selector = '.modal'
      const apply = (el) => {
        M.Modal.init(el)
      }
      main.attach(selector, apply)
    },
    requirePulse: () => {
      const selector = '.pulse'
      const apply = (el) => {
        el.addEventListener('click', e => {
          e.target.parentElement.classList.remove('pulse')
        })
      }
      main.attach(selector, apply)
    },
    requireTooltip: () => {
      const selector = '.tooltipped'
      const apply = (el) => {
        M.Tooltip.init(el)
      }
      main.attach(selector, apply)
    },
    requireFlashMessage: () => {
      const selector = '#snippet--flashes'
      const apply = (el) => {
        if (el.children && el.children.length) {
          const message = el.children[0].dataset.message
          M.toast({ html: message, displayLength: 2500 })
        }
      }
      main.attach(selector, apply)
    },
    requireConfirmation: () => {
      const selector = '[data-confirm]'
      const apply = (el) => {
        const cb = el.dataset.positive
        const modalSel = el.dataset.confirm
        if (!cb || !selector) return
        const ele = document.querySelector(modalSel)
        M.Modal.init(ele)
        el.addEventListener('click', function() {
          const positiveBtn = ele.querySelector('.modal-content > a.positive')
          positiveBtn.setAttribute('href', cb)
          const inst = M.Modal.getInstance(ele)
          inst.open()
        })
      }
      main.attach(selector, apply)
    }
  }
}

export const common = start(window)
