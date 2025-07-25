import { useForm } from '@inertiajs/react';
import React from 'react';

export default function Create() {
    const { data, setData, post, errors } = useForm({
        name: '',
        memo: ''
    });

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        post(route('items.store'));
    }

    return (
        <div className='bg-gray-600'>
            <div>
                {Object.entries(errors).map(([field, message]) => (
                    <div key={field} className="text-sm text-red-500">
                        {message}
                    </div>
                ))}
            </div>
            <form onSubmit={handleSubmit}>
                <div className='flex'>
                    <input type="text" className='bg-amber-200' name='name' value={data.name} onChange={(e) => setData('name', e.target.value)} />
                    <input type="text" name='memo' value={data.memo} onChange={(e) => setData('memo', e.target.value)} />
                    <button type="submit">作成</button>
                </div>
            </form>
        </div>
    )
}
