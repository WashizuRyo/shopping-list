import { Item, ShoppingList } from '@/types';
import { ItemForm } from '@/types/item';
import { Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function EditShoppingList({ shoppingList, items }: { shoppingList: ShoppingList; items: Item[] }) {
    const { data, setData, put, processing, errors } = useForm<{ name: string; items: ItemForm[] }>({
        name: shoppingList.name,
        items: shoppingList.items.map((item) => ({
            id: item.id,
            name: item.name,
            memo: item.memo || '',
            quantity: item.pivot?.quantity || 1,
            is_checked: item.pivot?.is_checked || false
        })),
    });

    function handleSubmit(event: React.FormEvent) {
        event.preventDefault();
        put(route('shopping_lists.update', shoppingList));
    }

    const [newItem, setNewItem] = useState({
        name: '',
        memo: '',
        quantity: 1,
        is_checked: false
    });

    const handleRemoveItem = (index: number) => {
        setData(
            'items',
            data.items.filter((_, i) => i !== index),
        );
    };

    const handleAddItem = () => {
        setData('items', [...data.items, newItem]);
        setNewItem({ name: '', memo: '', quantity: 1, is_checked: false });
    };

    const handleItemChange = (index: number, field: keyof ItemForm, value: string | number | boolean) => {
        setData(
            'items',
            data.items.map((item, i) => (i === index ? { ...item, [field]: value } : item)),
        );
    };

    return (
        <div className="p-4">
            <h1 className="mb-4 text-2xl font-bold">Edit Shopping List</h1>
            <Link href={route('shopping_lists.index')} className="text-blue-600 hover:text-blue-800 hover:underline">
                戻る
            </Link>
            {Object.entries(errors).map(([field, message]) => (
                <div key={field + message} className="text-sm text-red-500">
                    {message}
                </div>
            ))}

            <form onSubmit={handleSubmit} className="mt-6 rounded border p-4">
                <div className="mb-4">
                    <label className="mb-2 block">
                        Name:
                        <input
                            type="text"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            className="mt-1 w-full rounded border p-2"
                        />
                    </label>
                </div>
                <button
                    type="submit"
                    disabled={processing}
                    className="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 disabled:opacity-50"
                >
                    Save
                </button>
            </form>

            <div>Present Items</div>
            {items.map((item) => (
                <button
                    onClick={() => setData('items', [...data.items, { ...item, is_checked: false, quantity: 1 }])}
                    className="m-3 rounded bg-blue-500 p-3"
                >
                    {item.name}
                </button>
            ))}

            <div className="mt-8">
                <h2 className="mb-4 text-xl font-semibold">Items</h2>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label className="mb-2 block">
                            Item Name:
                            <input
                                type="text"
                                value={newItem.name}
                                onChange={(e) => setNewItem({ ...newItem, name: e.target.value })}
                                className="mt-1 w-full rounded border p-2"
                                required
                            />
                        </label>
                    </div>
                    <div>
                        <label className="mb-2 block">
                            Quantity:
                            <input
                                type="number"
                                value={newItem.quantity}
                                onChange={(e) => setNewItem({ ...newItem, quantity: Number(e.target.value) })}
                                className="mt-1 w-full rounded border p-2"
                                min="1"
                                required
                            />
                        </label>
                    </div>
                    <div>
                        <label className="mb-2 block">
                            Memo:
                            <input
                                type="text"
                                value={newItem.memo}
                                onChange={(e) => setNewItem({ ...newItem, memo: e.target.value })}
                                className="mt-1 w-full rounded border p-2"
                            />
                        </label>
                    </div>
                    <div className="flex items-end">
                        <button
                            type="submit"
                            className="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600 disabled:opacity-50"
                            onClick={() => handleAddItem()}
                        >
                            Add Item
                        </button>
                    </div>
                </div>

                <div className="rounded border">
                    {data.items && data.items.length > 0 ? (
                        <ul className="divide-y">
                            {data.items.map((item, i) => (
                                <li key={i} className="flex items-center justify-between p-4">
                                    <div className="flex flex-col gap-2 md:flex-row md:items-center">
                                        <input
                                            type="text"
                                            value={item.name}
                                            onChange={(e) => handleItemChange(i, 'name', e.target.value)}
                                            className="rounded border px-2 py-1 font-medium"
                                        />
                                        <input
                                            type="text"
                                            value={item.memo}
                                            onChange={(e) => handleItemChange(i, 'memo', e.target.value)}
                                            className="ml-2 rounded border px-2 py-1 text-gray-600"
                                            placeholder="メモ"
                                        />
                                        <input
                                            type="number"
                                            value={item.quantity}
                                            min={1}
                                            onChange={(e) => handleItemChange(i, 'quantity', Number(e.target.value))}
                                            className="ml-2 w-16 rounded border px-2 py-1 text-sm text-gray-500"
                                        />
                                        <input
                                            type="checkbox"
                                            checked={item.is_checked}
                                            onChange={(e) => handleItemChange(i, 'is_checked', e.target.checked)}
                                            className="ml-2 w-16 rounded border px-2 py-1 text-sm text-gray-500"
                                        />
                                    </div>
                                    <button
                                        onClick={() => handleRemoveItem(i)}
                                        className="rounded border border-red-300 px-3 py-1 text-red-600 hover:bg-red-50 hover:text-red-800"
                                    >
                                        削除
                                    </button>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="p-4 text-gray-500">No items added yet.</p>
                    )}
                </div>
            </div>
        </div>
    );
}
