import React from 'react'
import TableHead from './TableHead.jsx'
import TableBody from './TableBody.jsx'
import './Table.css'
  
function Table(props) { 
    const { basePrices, changeBasePrices, productPrices, changeProductPrices } = props
    return (
        <table class="table table-sm table-bordered table-price w-auto">
            <TableHead basePrices={basePrices} changeBasePrices={changeBasePrices} />
            <TableBody productPrices={productPrices} changeProductPrices={changeProductPrices} />
        </table>
    )
}

export default Table