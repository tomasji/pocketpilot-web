import qrcode from 'qrcode-generator-es6'

document.addEventListener('DOMContentLoaded', generateQR)

function generateQR(e) {
	const el = e.target.getElementById('qr')
	const form = e.target.querySelector('form')
	const email = form.querySelector('input[name=email]')
	const token = form.querySelector('input[name=token]')
	const qr = new qrcode(0, 'M') // eslint-disable-line
	qr.addData(email.value)
	qr.addData(';')
	qr.addData(token.value)
	qr.make()
	el.innerHTML = qr.createSvgTag({})
}
