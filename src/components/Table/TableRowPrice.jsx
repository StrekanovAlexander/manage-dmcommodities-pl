import React from 'react'

function TableRowPrice({ productPrice, changeProductPrices }) {
    return (
        <tr>
            <td>{productPrice.title}</td>
            <td>
                <input type="number" 
                    className="table-success"
                    name={`logistic-price-${productPrice.id}`}
                    value={productPrice.price} 
                    onChange={(ev) => 
                        changeProductPrices(productPrice.id, Number(ev.target.value))}
                />
            </td>
            {productPrice.products.map(product => 
                <td key={product.id}>
                    <div className="table-info">
                        {product.price}
                    </div>
                </td>
            )}
        </tr>    
    )
}

export default TableRowPrice;