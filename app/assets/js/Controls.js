class Controls {
	constructor(track) {
		this.track = track
		this._bind()
	}

	_bind() {
		document.addEventListener('DOMContentLoaded', (e) => {
			const controls = e.target.querySelector('.save-track > #frm-form')
			const submit = controls.querySelector('input[type="submit"][name="save"]')
			const hidden = controls.querySelector('input[name="waypoints"]')
			submit.addEventListener('click', () => {
				hidden.value = JSON.stringify(this.track.getWaypoints().map((wp) => wp.getLatLng()))
			})
		})
	}
}

export { Controls }
