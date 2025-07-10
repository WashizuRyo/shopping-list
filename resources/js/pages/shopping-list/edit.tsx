import { ShoppingList } from '@/types';
import { Link, useForm } from '@inertiajs/react';

export default function EditShoppingList({ shoppingList }: { shoppingList: ShoppingList }) {
    const { data, setData, put, processing } = useForm({
        name: shoppingList.name,
    });

    function handleSubmit(event: React.FormEvent) {
        event.preventDefault();
        put(route('shopping_lists.update', shoppingList));
    }

    return (
        <div>
            <h1>Edit Shopping List</h1>
            <Link href={route('shopping_lists.index')} className="text-blue-600 hover:text-blue-800 hover:underline">
                戻る
            </Link>
            <form onSubmit={handleSubmit} className="mt-4">
                <label>
                    Name:
                    <input type="text" value={data.name} onChange={(e) => setData('name', e.target.value)} />
                </label>
                <button type="submit" disabled={processing}>
                    Save
                </button>
            </form>
        </div>
    );
}
