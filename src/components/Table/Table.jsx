import React from 'react'
import TableHead from './TableHead.jsx'
import TableRowBase from './TableRowBase.jsx'
import TableRowPrice from './TableRowPrice.jsx'
import './Table.css'
  
function Table(props) { 
    const { basePrices, changeBasePrices, productPrices, changeProductPrices } = props
    return (
        <table class="table table-sm table-bordered table-price w-auto">
            <TableHead basePrices={basePrices} />
            <tbody>
                <TableRowBase basePrices={basePrices} changeBasePrices={changeBasePrices} />
                {productPrices.map(productPrice => 
                    <TableRowPrice 
                        key={productPrice.id} 
                        productPrice={productPrice}
                        changeProductPrices={changeProductPrices} 
                    />
                )}
            </tbody>
        </table>
    )
}

export default Table