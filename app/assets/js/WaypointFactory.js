import { DivIcon, DomUtil } from 'leaflet'
import { Waypoint } from './Waypoint'

const createWaypointIcon = (pulse) => {
	let classes = 'map-marker map-marker-circle'
	if (pulse) classes = classes + ' map-marker-pulse'
	return new DivIcon({
		className: classes,
		iconSize: null
	})
}
const removePulse = (object, clickEvent) => {
	DomUtil.removeClass(clickEvent.target._icon, 'map-marker-pulse')
	object.off('click', removePulse)
}

export function createEntryPoint(latlng, onAdd, onDrag, onDragEnd, onAddClick) {
	const o = new Waypoint(latlng, { icon: createWaypointIcon(true), draggable: true })
	const div = DomUtil.create('div')
	const addBtn = DomUtil.create('button')
	addBtn.innerHTML = '+'
	addBtn.addEventListener('click', () => onAddClick(o))
	div.appendChild(addBtn)
	o.bindPopup(div)
	o.on('add', () => onAdd(o))
	o.on('click', (e) => { removePulse(o, e) })
	o.on('drag', onDrag)
	o.on('dragend', () => onDragEnd(o))
	return o
}

export function createTurningPoint(latlng, onAdd, onRemove, onDrag, onDragEnd, onPopupOpen, onAddClick, onRemoveClick, onFinishClick) {
	const o = new Waypoint(latlng, { icon: createWaypointIcon(false), draggable: true })
	const div = DomUtil.create('div')
	const addBtn = DomUtil.create('button', 'add')
	addBtn.innerHTML = '+'
	addBtn.addEventListener('click', () => onAddClick(o))
	const removeBtn = DomUtil.create('button', 'remove')
	removeBtn.innerHTML = '-'
	removeBtn.addEventListener('click', () => onRemoveClick(o))
	const finishBtn = DomUtil.create('button', 'finish')
	finishBtn.innerHTML = 'F'
	finishBtn.addEventListener('click', () => onFinishClick(o))
	div.appendChild(addBtn)
	div.appendChild(removeBtn)
	div.appendChild(finishBtn)
	o.bindPopup(div)
	o.on('add', () => onAdd(o))
	o.on('drag', onDrag)
	o.on('dragend', () => onDragEnd(o))
	o.on('remove', () => onRemove(o))
	o.on('popupopen', () => onPopupOpen(o))
	return o
}
