import React from 'react'
import Table from '../Table/Table.jsx'
import PriceButtons from './PriceButtons.jsx'

function Price(props) {
    return props.basePrices.length && props.productPrices.length ? (
        <div>
            <Table 
                basePrices={props.basePrices} 
                changeBasePrices={props.changeBasePrices} 
                productPrices={props.productPrices}
                changeProductPrices={props.changeProductPrices}
            />
            <PriceButtons isPriceChanged={props.isPriceChanged} loadPrice={props.loadPrice}  />
        </div>
    ) : null
}

export default Price