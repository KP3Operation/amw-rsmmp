/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import InpatientListPage from "./InpatientList.vue";

describe("InpatientListPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(InpatientListPage);
        expect(wrapper.exists()).toBe(true);
    });
});
