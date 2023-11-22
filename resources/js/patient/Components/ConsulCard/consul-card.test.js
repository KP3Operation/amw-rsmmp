/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import ConsulCardComponent from "./ConsulCard.vue";

describe("ConsulCardComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(ConsulCardComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
