import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        items: [{ name: '', memo: '', quantity: 1 }],
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('shopping_lists.store'));
    };

    const addItem = () => {
        setData('items', [...data.items, { name: '', memo: '', quantity: 1 }]);
    };

    const removeItem = (index: number) => {
        const newItems = data.items.filter((_, i) => i !== index);
        setData('items', newItems);
    };

    const updateItem = (index: number, field: string, value: string | number) => {
        const newItems = data.items.map((item, i) => (i === index ? { ...item, [field]: value } : item));
        setData('items', newItems);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <div>
                {Object.entries(errors).map(([field, messages]) =>
                    Array.isArray(messages) ? (
                        messages.map((msg, i) => (
                            <div key={field + i} className="text-sm text-red-500">
                                {msg}
                            </div>
                        ))
                    ) : (
                        <div key={field} className="text-sm text-red-500">
                            {messages}
                        </div>
                    ),
                )}
            </div>
            <div className="mx-auto max-w-2xl p-6">
                <h1 className="mb-6 text-2xl font-bold">新しいショッピングリストを作成</h1>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <label htmlFor="name" className="mb-2 block text-sm font-medium text-gray-700">
                            リスト名
                        </label>
                        <input
                            type="text"
                            id="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required
                        />
                    </div>

                    <div>
                        <h2 className="mb-4 text-lg font-medium text-gray-900">アイテム</h2>

                        {data.items.map((item, index) => (
                            <div key={index} className="mb-4 rounded-lg border border-gray-200 p-4">
                                <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div>
                                        <label className="mb-1 block text-sm font-medium text-gray-700">アイテム名</label>
                                        <input
                                            type="text"
                                            value={item.name}
                                            onChange={(e) => updateItem(index, 'name', e.target.value)}
                                            className="w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                            placeholder="例: 牛乳"
                                        />
                                    </div>

                                    <div>
                                        <label className="mb-1 block text-sm font-medium text-gray-700">メモ</label>
                                        <input
                                            type="text"
                                            value={item.memo}
                                            onChange={(e) => updateItem(index, 'memo', e.target.value)}
                                            className="w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                            placeholder="例: 低脂肪"
                                        />
                                    </div>

                                    <div className="flex items-end gap-2">
                                        <div className="flex-1">
                                            <label className="mb-1 block text-sm font-medium text-gray-700">数量</label>
                                            <input
                                                type="number"
                                                min="1"
                                                value={item.quantity}
                                                onChange={(e) => updateItem(index, 'quantity', parseInt(e.target.value) || 1)}
                                                className="w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                            />
                                        </div>

                                        {data.items.length > 1 && (
                                            <button
                                                type="button"
                                                onClick={() => removeItem(index)}
                                                className="rounded-md border border-red-300 px-3 py-2 text-red-600 hover:bg-red-50 hover:text-red-800"
                                            >
                                                削除
                                            </button>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ))}

                        <button
                            type="button"
                            onClick={addItem}
                            className="w-full rounded-lg border-2 border-dashed border-gray-300 px-4 py-2 text-gray-600 transition-colors hover:border-gray-400 hover:text-gray-800"
                        >
                            + アイテムを追加
                        </button>
                    </div>

                    <div className="flex gap-4">
                        <button
                            type="submit"
                            disabled={processing}
                            className="flex-1 rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {processing ? '作成中...' : 'ショッピングリストを作成'}
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
