import React from 'react'

function PriceButtons(props) {
    return (
        <div style={{ display: props.isPriceChanged ? 'block' : 'none' }}>
            <button class="btn btn-success">Зберігти</button>
            <a href="#" class="btn btn-danger ml-2" onClick={props.loadPrice}>Скасувати</a>
        </div>
    )
}

export default PriceButtons