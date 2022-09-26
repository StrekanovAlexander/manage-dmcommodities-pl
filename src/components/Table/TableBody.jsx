import React from 'react'
import TableRow from './TableRow.jsx'

function TableBody({ productPrices, changeProductPrices }) {
    return (
        <tbody>
            {productPrices.map(productPrice => 
                <TableRow 
                    key={productPrice.id} 
                    productPrice={productPrice}
                    changeProductPrices={changeProductPrices} 
                />
            )}
        </tbody>
    )
}

export default TableBody