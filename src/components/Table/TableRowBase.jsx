import React from 'react'

function TableRowBase({ basePrices, changeBasePrices }) {
    return (
        <tr>
            <td></td>
            <td></td>
            {basePrices.map(el => 
                <td key={el.id}>
                    <input type="number"
                        className="table-success" 
                        name={`base-price-${el.id}`}
                        value={el.price} 
                        onChange={(ev) => 
                            changeBasePrices(el.id, Number(ev.target.value))}
                    />
                </td>
            )}
        </tr>
    )    
}

export default TableRowBase