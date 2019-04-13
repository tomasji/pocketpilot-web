import naja from 'naja'
import M from 'materialize-css'
import netteForms from './nette-forms'

naja.formsHandler.netteForms = netteForms
document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja))

naja.snippetHandler.addEventListener('afterUpdate', (event) => {
	if (event.snippet.id === 'snippet--flashes') {
		const message = event.snippet.children[0].dataset.message
		M.toast({ html: message, displayLength: 2500 })
	}
})
