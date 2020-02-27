import $ from 'DOMLoaded'

class Controls {
	constructor(track, table) {
		this.track = track
		this.table = table
		this._bind()
	}

	_bind() {
		$(() => {
			const form = document.querySelector('.save-track > form')
			const control = document.querySelector('.controls-buttons > .save')
			const hidden = form.querySelector('input[name="waypoints"]')
			control.addEventListener('click', () => {
				hidden.value = JSON.stringify(this.track.getWaypoints().map((wp) => wp.getLatLng()))
			})
		})

		$(() => {
			const speed = document.querySelector('.controls-speed input[type="text"][name="speed"]')
			speed.addEventListener('input', (e) => {
				this.table.recalculateTimes(e.target.value)
			})
		})
	}
}

export { Controls }
