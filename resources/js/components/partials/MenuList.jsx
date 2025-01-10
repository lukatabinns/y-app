import React from "react";

const MenuList = ({ menus, guests}) => {
    return (
        <div>
            <div className="menu-grid">
                {/* Iterate over the menus array and render each menu */}
                {menus.map((menu) => {
                    // Extract price per person and minimum spend, with fallback values
                    const pricePerPerson = menu.price_per_person || 0;
                    const minimumSpend = menu.min_spend || 0;
                    const numberOfGuests = guests || 1;

                    // Calculate the total price as the greater of the per-person price or minimum spend
                    const totalPrice = Math.max(pricePerPerson * numberOfGuests, minimumSpend);

                    return (
                        <div key={menu.id} className="menu-item">
                            <img src={menu.thumbnail} alt={menu.name} className="menu-thumbnail" />
                            <div className="menu-details">
                                <h3>{menu.name}</h3>
                                <p>{menu.description}</p>
                                <div className="price">Total Price: ${totalPrice.toFixed(2)}</div>
                                <div className="cuisines">{menu.cuisines[0].name}</div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
};

export default MenuList;
