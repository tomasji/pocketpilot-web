class Controls {
	constructor(track, table) {
		this.track = track
		this.table = table
		this._bind()
	}

	_bind() {
		document.addEventListener('DOMContentLoaded', (e) => {
			const form = e.target.querySelector('.save-track > form')
			const control = e.target.querySelector('.controls-buttons > .save')
			const hidden = form.querySelector('input[name="waypoints"]')
			control.addEventListener('click', () => {
				hidden.value = JSON.stringify(this.track.getWaypoints().map((wp) => wp.getLatLng()))
			})
		})

		document.addEventListener('DOMContentLoaded', (e) => {
			const speed = e.target.querySelector('.controls-speed input[type="text"][name="speed"]')
			speed.addEventListener('input', (e) => {
				this.table.recalculateTimes(e.target.value)
			})
		})
	}
}

export { Controls }
